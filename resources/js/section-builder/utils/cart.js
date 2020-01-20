const BIZINZBOX_CART_STORAGE_PREFIX = 'BIZINZBOX_CART_STORAGE_FOR_WEB_'

class Cart {
  webId = ''

  hasCart = false

  items = []

  total = 0

  totalQuantity = 0

  subtotal = 0

  shipping = 0

  shippingAddress = {
    email: '',
    first_name: '',
    last_name: '',
    address: '',
    city: '',
    state: '',
    zip: '',
    phone: '',
    notes: ''
  }

  constructor(webId) {
    if (!webId) {
      throw new Error('missing web id')
    }
    this.webId = webId
    const oldCart = localStorage.getItem(this.getKey())
    if (oldCart) {
      const cartObject = JSON.parse(oldCart)
      this.items = cartObject.items
      this.total = cartObject.total
      this.totalQuantity = cartObject.totalQuantity
      this.subtotal = cartObject.subtotal
      this.shipping = cartObject.shipping
      this.shippingAddress = cartObject.shippingAddress
    }
  }

  getKey() {
    return BIZINZBOX_CART_STORAGE_PREFIX + this.webId
  }

  save() {
    localStorage.setItem(this.getKey(), JSON.stringify(this))
  }

  calculateTotal() {
    this.subtotal = this.items.reduce((sum, item) => {
      sum += item.price * item.quantity
      return sum
    }, 0)

    this.totalQuantity = this.items.reduce((sum, item) => {
      sum += item.quantity
      return sum
    }, 0)

    this.total = this.subtotal + this.shipping

    this.save()
  }

  addItem(product, quantity, options, price) {
    const itemIndex = this.items.findIndex((cartItem) => {
      const isProductMatching =
        cartItem.product.id === product.id &&
        (!product.size || cartItem.size === options.size) &&
        (!product.color || cartItem.color === options.color) &&
        (!product.variant || cartItem.variant === options.variant)

      return isProductMatching
    })

    if (itemIndex === -1) {
      this.items.push({
        price: price,
        product,
        quantity,
        ...options
      })
    } else {
      this.items[itemIndex].quantity += quantity
    }
    this.calculateTotal()
  }

  async updateItem(product, amount = 1) {
    const itemIndex = this.items.findIndex((item) => item.product.id === product.id)

    if (amount === 0 && itemIndex === -1) {
      return this
    }

    if (amount === 0 && itemIndex > -1) {
      this.items.splice(itemIndex, 1)
    }

    if (amount > 0) {
      if (itemIndex === -1) {
        this.items.push({
          product,
          quantity: amount,
          price: product.price * amount
        })
      } else {
        this.items[itemIndex].quantity = amount
      }
    }

    this.calculateTotal()
    this.hasCart = this.items.length > 0
    this.save()

    return this
  }

  deleteItem(product_id) {
    const itemIndex = this.items.findIndex((cartItem) => cartItem.product.id === product_id)
    if (itemIndex > -1) {
      this.items.splice(itemIndex, 1)
    }
    this.calculateTotal()
  }

  updateShippingAddress(address) {
    this.shippingAddress = address

    this.save()
  }

  clear() {
    localStorage.removeItem(this.getKey())
  }
}

export default Cart
