const pageMap = (allPages, router) => {
  if (router.path.startsWith('/product')) {
    return {
      name: 'Product Detail'
    }
  } else if (router.path === '/cart') {
    return {
      name: 'Cart Page'
    }
  } else if (router.path.includes('/checkout')) {
    return {
      name: 'Checkout Page'
    }
  }

  return allPages.find((page) => (page.url || '/') === (router.path || '/'))
}

export default pageMap
