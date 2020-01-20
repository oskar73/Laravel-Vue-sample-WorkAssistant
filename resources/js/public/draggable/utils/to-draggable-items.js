import { getIdGenerator } from "./id-generator";

const draggableItemIdGenrator = getIdGenerator();

export const toDraggableItems = (arr) => {
  return arr.map((e) => ({
    id: draggableItemIdGenrator(),
    data: e,
  }));
};

export const toOriginalArray = (arr) => {
  return arr.map((e) => e.data);
};
