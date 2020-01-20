const { createServer } = require('http')
const { Server } = require('socket.io')
require('dotenv').config()
const IoRedis = require('ioredis')
const SOCKET_PORT = process.env.SOCKET_PORT ?? 3000
const REDIS = {
  host: process.env.REDIS_HOST,
  port: process.env.REDIS_PORT,
  password: process.env.REDIS_PASSWORD,
  family: 4,
  db: process.env.REDIS_SOCKET_DB
}
const PREFIX = process.env.REDIS_PREFIX

const redis = new IoRedis(REDIS)

let options = {}

if (process.env.APP_ENV === 'staging') {
  const fs = require('fs')
  options = {
    key: fs.readFileSync('./ssl/private.key').toString(),
    cert: fs.readFileSync('./ssl/certificate.crt').toString()
  }
}

const httpServer = createServer(options)

const io = new Server(httpServer, {
  cors: {
    origin: ['https://bizinabox.test'],
    methods: ['GET', 'POST'],
    credentials: true
  }
})

httpServer.listen(SOCKET_PORT, function () {
  console.log(new Date() + ' - Server is running on port ' + SOCKET_PORT + ' and listening Redis on port ' + REDIS.port + '!')
})

io.on('connection', function (socket) {
  socket.on('joinguest', (data) => {
    try {
      const channel = PREFIX + 'guest-' + data
      socket.join(channel)
    } catch (err) {
      console.log('joinguest:' + err.messagee)
    }
  })
  socket.on('joinuser', (data) => {
    try {
      const channel = PREFIX + 'user-' + data
      socket.join(channel)
    } catch (err) {
      console.log('joinuser:' + err.messagee)
    }
  })
  socket.on('jointeam', (data) => {
    try {
      const channel = PREFIX + 'team-' + data
      socket.join(channel)
    } catch (err) {
      console.log('jointeam:' + err.messagee)
    }
  })
  socket.on('guest-typing', (data) => {
    try {
      const channel = PREFIX + 'guest-' + data.to_id
      io.to(channel).emit('guest-typing', data)
    } catch (err) {
      console.log('guest-typing:' + err.messagee)
    }
  })
  socket.on('user-typing', (data) => {
    try {
      const channel = PREFIX + 'user-' + data.to_id
      io.to(channel).emit('user-typing', data)
    } catch (err) {
      console.log('user-typing:' + err.messagee)
    }
  })
  socket.on('team-typing', (data) => {
    try {
      const channel = PREFIX + 'team-' + data.to_id
      io.to(channel).emit('team-typing', data)
    } catch (err) {
      console.log('team-typing:' + err.messagee)
    }
  })
  socket.on('guest-end', (data) => {
    try {
      const channel = PREFIX + 'guest-' + data.id
      io.to(channel).emit('guest-end', data)

      io.of('/')
        .in(channel)
        .clients((error, socketIds) => {
          if (error) throw error
          socketIds.forEach((socketId) => io.sockets.sockets[socketId].leave('chat'))
        })
    } catch (err) {
      console.log('guest-end:' + err.messagee)
    }
  })
})

redis.psubscribe('*')

redis.on('pmessage', function (subscribed, channel, data) {
  try {
    io.to(channel).emit(JSON.parse(data).emit, data)
  } catch (err) {
    console.log('pmessage:' + err.messagee)
  }
})
