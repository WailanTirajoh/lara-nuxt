import { defineFormKitConfig } from "@formkit/vue";
import { generateClasses } from "@formkit/themes";
import { genesisIcons } from "@formkit/icons";
import { createAutoAnimatePlugin } from "@formkit/addons";
import tailwindTheme from "./tailwind-theme.js";

export default defineFormKitConfig(() => {
  return {
    icons: {
      ...genesisIcons,
    },
    config: {
      classes: generateClasses(tailwindTheme),
    },
    plugins: [createAutoAnimatePlugin()],
  };
});
