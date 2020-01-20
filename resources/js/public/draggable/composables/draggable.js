import { ref, onMounted, onUpdated, watch } from "vue";
import { changeArrayOrder } from "../utils/change-order";
import { getIdGenerator } from "../utils/id-generator";
import { throttle } from "../utils/throttle";
import { toOriginalArray, toDraggableItems } from "../utils/to-draggable-items";

let itemCurrentlyDragging = ref(null);
let containerIdCurrentlyDraggedOver = ref(null);
let transitioning = false;
const containerIdGenerator = getIdGenerator();

const useDraggableContainer = (
  originalItems,
  context
) => {
  const id = containerIdGenerator();
  const items = ref(
    toDraggableItems(originalItems.value)
  );

  // update v-model when dropped
  watch(originalItems, () => {
    items.value = items.value.map((item, index) => {
      return {
        ...item,
        data: originalItems.value[index]
      }
    })
  });

  // update v-model when dropped
  watch(itemCurrentlyDragging, () => {
    if (itemCurrentlyDragging.value) {
      return;
    }
    context.emit("update:modelValue", toOriginalArray(items.value));
  });

  // case when an item is being dragged to another container
  watch(containerIdCurrentlyDraggedOver, () => {
    if (containerIdCurrentlyDraggedOver.value === id) {
      return;
    }
    items.value = items.value.filter(
      (item) => item.id !== itemCurrentlyDragging.value.id
    );
  });

  // when an item is moved to an empty container
  const onDragOver = () => {
    if (
      transitioning ||
      !itemCurrentlyDragging.value ||
      containerIdCurrentlyDraggedOver.value === id
    ) {
      return;
    }

    if (items.value.length > 0) {
      return;
    }

    containerIdCurrentlyDraggedOver.value = id;
    items.value = [itemCurrentlyDragging.value];
  };

  // handle event emitted from draggableItem
  const onItemDragOver = ({ position }) => {
    if (transitioning || !itemCurrentlyDragging.value) {
      return;
    }
    items.value = changeArrayOrder(
      items.value,
      itemCurrentlyDragging.value,
      position
    );
  };

  return {
    id,
    items,
    onDragOver,
    onItemDragOver,
  };
};

const useDraggableItem = (
  item,
  position,
  containerId,
  context
) => {
  const draggableItemEl = ref(null);
  const isDragging = ref(
    item.value.id === itemCurrentlyDragging.value?.id
  );
  const middleY = ref(null);

  onMounted(async () => {
    const box = draggableItemEl.value.getBoundingClientRect();
    middleY.value = box.top + box.height / 2;
  });

  onUpdated(() => {
    const box = draggableItemEl.value.getBoundingClientRect();
    middleY.value = box.top + box.height / 2;
  });

  const onDragStart = () => {
    itemCurrentlyDragging.value = item.value;
    containerIdCurrentlyDraggedOver.value = containerId.value;
    isDragging.value = true;
  };

  const onDragEnd = () => {
    itemCurrentlyDragging.value = null;
  };

  const onDragOver = throttle((e) => {
    if (item.value.id === itemCurrentlyDragging.value.id) {
      return;
    }

    if (containerIdCurrentlyDraggedOver.value !== containerId.value) {
      containerIdCurrentlyDraggedOver.value = containerId.value;
    }

    const offset = middleY.value - e.clientY;

    context.emit("itemDragOver", {
      position: offset > 0 ? position.value : position.value + 1,
    });
  }, 50);

  const transitionStart = () => {
    transitioning = true;
  };

  const transitionEnd = () => {
    transitioning = false;
  };

  watch(itemCurrentlyDragging, () => {
    if (itemCurrentlyDragging.value) {
      return;
    }
    isDragging.value = false;
  });

  return {
    draggableItemEl,
    isDragging,
    onDragStart,
    onDragOver,
    onDragEnd,
    transitionStart,
    transitionEnd,
  };
};

export { useDraggableContainer, useDraggableItem };
