export const stringToSlug = (str: string) => {
  // Step 1: Remove special characters and spaces
  str = str.replace(/[^\w\s-]/g, "");

  // Step 2: Convert the string to lowercase
  str = str.toLowerCase();

  // Step 3: Replace spaces with hyphens
  str = str.replace(/\s+/g, "-");

  return str;
};
