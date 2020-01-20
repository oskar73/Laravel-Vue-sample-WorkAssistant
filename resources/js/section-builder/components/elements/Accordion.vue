<template>
  <div class="accordion box" role="presentation">
    <accordion-item v-for="item in content" :multiple="multiple" :item="item" :groupId="groupId" :key="item.id"> </accordion-item>
  </div>
</template>

<script>
import AccordionItem from '../../components/elements/AccordionItem'
export default {
  name: 'accordion',
  components: { AccordionItem },
  props: {
    content: {
      type: Array,
      required: true
    },
    multiple: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      groupId: null
    }
  },
  mounted() {
    this.groupId = this.$el.id
  }
}
</script>

<style lang="scss" scoped>
.accordion {
  padding: 0;

  div:not(:last-child) {
    border-bottom: 1px solid rgba(10, 10, 10, 0.1);
  }

  div:last-child .accordion-item-details {
    border-radius: 5px;
  }

  dd {
    margin-left: 0;
  }
}
</style>
