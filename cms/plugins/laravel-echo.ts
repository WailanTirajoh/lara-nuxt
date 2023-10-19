import LaravelEcho from "laravel-echo";
import Pusher from "pusher-js";

declare global {
  interface Window {
    Pusher: typeof Pusher;
  }
}

export default defineNuxtPlugin(() => {
  const runtimeConfig = useRuntimeConfig();
  window.Pusher = Pusher;
  const Echo = new LaravelEcho({
    broadcaster: "pusher",
    key: runtimeConfig.public.key,
    wsHost: runtimeConfig.public.wsHost,
    wsPort: runtimeConfig.public.wsPort,
    wssPort: runtimeConfig.public.wssPort,
    forceTLS: false,
    encrypted: true,
    enableLogging: true,
    disableStats: true,
    enabledTransports: ["ws", "wss"],
    cluster: "mt1",
  });

  return {
    provide: {
      echo: Echo,
    },
  };
});
