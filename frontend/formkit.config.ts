import { defineFormKitConfig } from "@formkit/vue";
import { generateClasses } from "@formkit/themes";
import { genesisIcons } from "@formkit/icons";
import tailwindTheme from "./tailwind-theme.js";

export default defineFormKitConfig(() => {
  return {
    icons: {
      ...genesisIcons,
    },
    config: {
      classes: generateClasses(tailwindTheme),
    },
  };
});
