import templateMixin from '../mixins/templateMixin'

export default {
  mixins: [templateMixin],
  computed: {
    pageData() {
      const activePage = this.allPages[this.indexOfActivePage]
      if (activePage) {
        return {
          pageId: activePage.id,
          index: this.indexOfActivePage
        }
      }
    }
  }
}
