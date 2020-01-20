import axios from 'axios'

const getDesignData = (hash, ownerHash) => {
  return new Promise((resolve) => {
    axios
      .get(window.route('graphics.get', { designHash: hash, ownerHash }))
      .then((response) => {
        if (response.status === 200 && response.data.status) {
          resolve(response.data.data)
        } else {
          console.error('graphics.get error: ', response)
        }
      })
      .catch((err) => {
        console.error('graphics.get error: ', err)
      })
  })
}

const chooseNewDesign = (hash, data) => {
  return new Promise((resolve) => {
    axios
      .get(window.route('graphics.choose', hash), data)
      .then((response) => {
        if (response.status === 200 && response.data.status) {
          resolve(response.data.data)
        } else {
          console.error('graphics.choose error: ', response)
        }
      })
      .catch((err) => {
        console.error('graphics.choose error: ', err)
      })
  })
}

const chooseGraphic = (slug) => {
  return new Promise((resolve) => {
    axios
      .get(window.route('graphics.category.designs', { slug }))
      .then((response) => {
        if (response.status === 200 && response.data.status) {
          resolve(response.data.data)
        } else {
          console.error('graphics.category.designs error: ', response)
        }
      })
      .catch((err) => {
        console.error('graphics.category.designs error: ', err)
      })
  })
}

export { getDesignData, chooseNewDesign, chooseGraphic }
