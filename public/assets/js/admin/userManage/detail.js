var websiteTable,
  domainTable,
  readyMadeTable,
  packageTable,
  pluginTable,
  serviceTable,
  lacarteTable,
  blogTable,
  blogAdsTable,
  orderTable,
  subscriptionTable,
  transactionTable,
  purchaseFollowupTable,
  appointmentTable,
  ticketTable,
  loginTable,
  directoryListingTable,
  designsTable,
  couponsTable

$(function () {
  hashUpdate(window.location.hash)
  $('.selectpicker').selectpicker()
  $('.select2').select2({
    width: '100%'
  })
  $('.front-dt').DataTable()
  websiteTable = $('#website_area .datatable-all').DataTable(websiteSetParam('all'))
  domainTable = $('#domain_area .datatable-all').DataTable(domainSetParam('all'))
  readyMadeTable = $('#readyMadeBiz_area .datatable-all').DataTable(readyMadeSetParam('all'))
  packageTable = $('#package_area .datatable-all').DataTable(packageSetParam('all'))
  pluginTable = $('#plugin_area .datatable-all').DataTable(pluginSetParam('all'))
  serviceTable = $('#service_area .datatable-all').DataTable(serviceSetParam('all'))
  lacarteTable = $('#lacarte_area .datatable-all').DataTable(lacarteSetParam('all'))
  blogTable = $('#blog_area .datatable-all').DataTable(blogSetParam('all'))
  blogAdsTable = $('#blogAds_area .datatable-all').DataTable(blogAdsSetParam('all'))
  orderTable = $('#order_area .datatable-all').DataTable(orderSetParam('all'))
  subscriptionTable = $('#subscription_area .datatable-all').DataTable(subscriptionSetParam('all'))
  transactionTable = $('#transaction_area .datatable-all').DataTable(transactionSetParam('all'))
  purchaseFollowupTable = $('#purchase_followup_area .datatable-all').DataTable(purchaseFollowupSetParam('all'))
  appointmentTable = $('#appointment_area .datatable-all').DataTable(appointmentSetParam('all'))
  ticketTable = $('#ticket_area .datatable-all').DataTable(ticketSetParam('all'))
  loginTable = $('#login_area .datatable-all').DataTable(loginSetParam('all'))
  directoryListingTable = $('#directory_listings_area .datatable-all').DataTable(directoryListingSetParam('all'))
  portfolioSetParam()
  designsTable = $('#designs_area .datatable-all').DataTable(designsSetParam('all'))
  couponsTable = $('#coupons_area .datatable-all').DataTable(couponsSetParam('all'))
})
function websiteSetParam(status) {
  let ajax = {
    url: '/admin/website/list',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'name', name: 'name' },
    { data: 'domain', name: 'domain' },
    { data: 'status', name: 'status' },
    { data: 'status_by_owner', name: 'status_by_owner' },
    { data: 'storage', name: 'storage', orderable: false, searchable: false },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 4, false)
}
function domainSetParam(status) {
  let ajax = {
    url: '/admin/domainList',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'domainID', name: 'domainID' },
    { data: 'name', name: 'name' },
    { data: 'website', name: 'website' },
    { data: 'orderID', name: 'orderID' },
    { data: 'transactionID', name: 'transactionID' },
    { data: 'chargedAmountNC', name: 'chargedAmountNC' },
    { data: 'chargedAmountBB', name: 'chargedAmountBB' },
    { data: 'created_at', name: 'created_at' },
    { data: 'expired_at', name: 'expired_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 7, false)
}
function readyMadeSetParam(status) {
  let ajax = {
    url: '/admin/purchase/readymade',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'order', name: 'order' },
    { data: 'itemName', name: 'itemName' },
    { data: 'payment', name: 'payment' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'due_date', name: 'due_date' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 4, false)
}
function packageSetParam(status) {
  let ajax = {
    url: '/admin/purchase/package',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'order', name: 'order' },
    { data: 'itemName', name: 'itemName' },
    { data: 'payment', name: 'payment' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'due_date', name: 'due_date' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 4, false)
}

function pluginSetParam(status) {
  let ajax = {
    url: '/admin/purchase/plugin',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'order', name: 'order' },
    { data: 'itemName', name: 'itemName' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 4, false)
}

function serviceSetParam(status) {
  let ajax = {
    url: '/admin/purchase/service',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'order', name: 'order' },
    { data: 'itemName', name: 'itemName' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 4, false)
}

function lacarteSetParam(status) {
  let ajax = {
    url: '/admin/purchase/lacarte',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'order', name: 'order' },
    { data: 'itemName', name: 'itemName' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 4, false)
}
function blogSetParam(status) {
  let ajax = {
    url: '/admin/blog/post',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'category', name: 'category' },
    { data: 'title', name: 'title' },
    { data: 'view_count', name: 'view_count' },
    { data: 'favoriters', name: 'favoriters' },
    { data: 'comments', name: 'comments' },
    { data: 'subscribers', name: 'subscribers' },
    { data: 'is_free', name: 'is_free' },
    { data: 'featured', name: 'featured' },
    { data: 'is_published', name: 'is_published' },
    { data: 'status', name: 'status' },
    { data: 'visible_date', name: 'visible_date' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 12, false)
}
function blogAdsSetParam(status) {
  let ajax = {
    url: '/admin/blogAds/listing',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'spot', name: 'spot' },
    { data: 'page', name: 'page' },
    { data: 'price', name: 'price' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 5, false)
}

function orderSetParam(status) {
  let ajax = {
    url: '/admin/purchase/order',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'id', name: 'id' },
    { data: 'gateway', name: 'gateway' },
    { data: 'price', name: 'price' },
    { data: 'total_qty', name: 'total_qty' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 4, false)
}
function subscriptionSetParam(status) {
  let ajax = {
    url: '/admin/purchase/subscription',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'product_type', name: 'product_type' },
    { data: 'product_name', name: 'product_name' },
    { data: 'price_detail', name: 'price_detail' },
    { data: 'order_id', name: 'order_id' },
    { data: 'status', name: 'status' },
    { data: 'due_date', name: 'due_date' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 6, false)
}

function transactionSetParam(status) {
  let ajax = {
    url: '/admin/purchase/transaction',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'gateway', name: 'gateway' },
    { data: 'amount', name: 'amount' },
    { data: 'invoice', name: 'invoice' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 3, false)
}
function purchaseFollowupSetParam(status) {
  let ajax = {
    url: '/admin/purchase/form',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'model_type', name: 'model_type', orderable: false, searchable: false },
    { data: 'model_name', name: 'model_name', orderable: false, searchable: false },
    { data: 'title', name: 'title' },
    { data: 'description', name: 'description' },
    { data: 'status', name: 'status' },
    { data: 'read_at', name: 'read_at' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 7, false)
}

function appointmentSetParam(status) {
  let ajax = {
    url: '/admin/appointment/listing/getData',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'date', name: 'date' },
    { data: 'time', name: 'time' },
    { data: 'status', name: 'status' },
    { data: 'product', name: 'product' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 1, false)
}

function ticketSetParam(status) {
  let ajax = {
    url: '/admin/ticket/item',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'id', name: 'id' },
    { data: 'category', name: 'category' },
    { data: 'text', name: 'text' },
    { data: 'priority', name: 'priority' },
    { data: 'status', name: 'status' },
    { data: 'created_at', name: 'created_at' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 6, false)
}

function loginSetParam(status) {
  let ajax = {
    url: '/admin/userManage/getLogin',
    type: 'get',
    data: { status: status, user: user_id }
  }

  let columns = [
    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
    { data: 'created_at', name: 'created_at' },
    { data: 'ip', name: 'ip' },
    { data: 'device', name: 'device' },
    { data: 'action', name: 'action', orderable: false }
  ]

  return setTbl(ajax, columns, 2, false)
}

function directoryListingSetParam(status) {
    let ajax = {
        url: '/admin/directory/listing',
        type: 'get',
        data: { status: status, user: user_id }
      }

      let columns = [
        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
        { data: 'category', name: 'category' },
        { data: 'title', name: 'title' },
        // { data: 'user', name: 'user', orderable: false, searchable: false },
        { data: 'purchased', name: 'purchased' },
        { data: 'property', name: 'property' },
        { data: 'view_count', name: 'view_count' },
        { data: 'featured', name: 'featured' },
        { data: 'status', name: 'status' },
        { data: 'expired_at', name: 'expired_at' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false }
      ]

      return setTbl(ajax, columns, 2, false)
}

function portfolioSetParam() {
    $.ajax({
        url: '/admin/portfolio/item?user='+user_id,
        type: 'get',
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (result) {
          if (result.status === 1) {
            $('.show_checked').addClass('d-none')

            $('#portfolios_area .m-portlet__body').html(result.all)
          }
        },
        error: function (e) {
          console.log('g', e)
        }
      })
}

function designsSetParam(status) {
    let ajax = {
        url: '/admin/graphics/user/' + user_id + '/designs',
        type: 'get',
        data: { status: status, user: user_id }
    }

    let columns = [
        { data: 'preview', name: 'preview' },
        { data: 'download1', name: 'download1' },
        { data: 'download2', name: 'download2' },
        { data: 'type', name: 'premium' },
        { data: 'edit', name: 'edit' },
        { data: 'view_live', name: 'view_live' },
        { data: 'created_at', name: 'created_at' },
        { data: 'updated_at', name: 'updated_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ]

    return setTbl(ajax, columns, 6, false)
}

function couponsSetParam(status) {
    let ajax = {
        url: '/admin/coupon',
        type: 'get',
        data: { status: status, user: user_id }
    }

    let columns = [
        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'code', name: 'code' },
        { data: 'type', name: 'type', orderable: false, searchable: false },
        { data: 'product', name: 'product', orderable: false, searchable: false },
        { data: 'user', name: 'User', orderable: false, searchable: false },
        { data: 'discount', name: 'discount' },
        { data: 'reusable', name: 'reusable' },
        { data: 'status', name: 'Status' },
        { data: 'expired_at', name: 'expired_at' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false }
    ]

    return setTbl(ajax, columns)
}
