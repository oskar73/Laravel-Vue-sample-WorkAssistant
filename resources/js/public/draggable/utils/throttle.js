export const throttle = (callback, limit) => {
  let waiting = false;
  return (...args) => {
    if (waiting) {
      return;
    }
    callback(...args);
    waiting = true;
    setTimeout(() => {
      waiting = false;
    }, limit);
    return;
  };
};
