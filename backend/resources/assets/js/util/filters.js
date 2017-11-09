export const toFixed = (val, decimals = 1) => {
  return parseFloat(val).toFixed(decimals);
};

export const mToFt = val => parseFloat(val) * 3.28084;

export const removeSeconds = time => time.split(':').slice(0, 2).join(':');
