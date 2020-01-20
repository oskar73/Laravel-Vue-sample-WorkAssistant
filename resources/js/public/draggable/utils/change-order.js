export const changeArrayOrder = (
  arr,
  target,
  newIndexOfTarget
) => {
  let newArr = arr.filter((e) => e.id !== target.id);
  newArr.splice(newIndexOfTarget, 0, { ...target });
  return newArr;
};
