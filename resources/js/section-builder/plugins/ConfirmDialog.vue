<template>
  <div class="overlay bz-confirm-dialog">
    <div v-click-outside="close" class="dialog">
      <span class="close-dialog" @click="close">X</span>
      <div class="dialog-header">{{ title }}</div>
      <div class="dialog-body" v-html="description"></div>
      <div class="dialog-footer">
        <button class="btn-yes" @click="confirm">{{ action[0] }}</button>
        <button class="btn-no" @click="close">{{ action[1] }}</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ConfirmDialog',
  props: {
    title: {
      type: String,
      default: 'Confirmation'
    },
    description: {
      type: String,
      default: 'Are you sure with this action?'
    },
    action: {
      type: Array,
      default: () => ['Yes', 'No']
    }
  }
}
</script>

<style lang="scss" scoped>
.overlay {
  width: 100vw;
  height: 100vh;
  background-color: #00000034;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 10001;
  top: 0;
  left: 0;
  position: fixed;

  .dialog {
    background-color: white;
    border-radius: 4px;
    box-shadow: 0 0 4px 2px #00000012;
    border: solid 1px #00000012;
    min-width: 360px;
    max-width: 800px;
    position: relative;

    .close-dialog {
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
      font-weight: 700;
      font-size: 20px;
      color: #0a0a0a;
    }

    .dialog-header {
      padding: 20px 20px;
      border-bottom: solid 1px #00000012;
      font-size: 18px;
      font-weight: 700;
      color: #555555;
    }

    .dialog-body {
      padding: 20px 20px;
      background: #f3f4f6;
    }

    .dialog-footer {
      border-top: solid 1px #00000012;
      display: flex;
      justify-content: flex-end;
      padding: 10px 20px;

      .btn-yes,
      .btn-no {
        padding: 8px 15px;
        min-width: 60px;
        text-align: center;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        margin-left: 15px;
      }
      .btn-yes {
        background-color: #0a8cf0;
      }
      .btn-no {
        background-color: #afafaf;
      }
    }
  }
}
</style>
