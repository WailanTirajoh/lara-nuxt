import LaravelEcho from "laravel-echo";
import Pusher from "pusher-js";

declare global {
  interface Window {
    Pusher: typeof Pusher;
    Echo: typeof LaravelEcho;
  }
}

export default defineNuxtPlugin(() => {
  const runtimeConfig = useRuntimeConfig();

  window.Pusher = Pusher;
  const Echo = new LaravelEcho({
    authorizer: (channel: any, options: any) => {
      return {
        authorize: (socketId: any, callback: any) => {
          $useFetchAPI(`/broadcasting/auth`, {
            baseURL: runtimeConfig.public.wsBaseUrl,
            method: "POST",
            body: {
              socket_id: socketId,
              channel_name: channel.name,
            },
          })
            .then((data) => {
              callback(false, data);
            })
            .catch((error) => {
              callback(true, error);
            });
        },
      };
    },
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
