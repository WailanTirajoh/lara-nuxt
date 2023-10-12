import { FetchResult, UseFetchOptions } from "nuxt/app";
import type { NitroFetchRequest, AvailableRouterMethod } from "nitropack";
import type { FetchError } from "ofetch";
import type {
  AsyncData,
  KeysOf,
  PickFrom,
} from "nuxt/dist/app/composables/asyncData";

export const useFetchAPI = async <
  ResT = void,
  ErrorT = FetchError,
  ReqT extends NitroFetchRequest = NitroFetchRequest,
  Method extends AvailableRouterMethod<ReqT> = ResT extends void
    ? "get" extends AvailableRouterMethod<ReqT>
      ? "get"
      : AvailableRouterMethod<ReqT>
    : AvailableRouterMethod<ReqT>,
  _ResT = ResT extends void ? FetchResult<ReqT, Method> : ResT,
  DataT = _ResT,
  PickKeys extends KeysOf<DataT> = KeysOf<DataT>,
  DefaultT = null
>(
  request: Ref<ReqT> | ReqT | (() => ReqT),
  opts?: UseFetchOptions<_ResT, DataT, PickKeys, DefaultT, ReqT, Method>
): Promise<AsyncData<PickFrom<DataT, PickKeys> | DefaultT, ErrorT | null>> => {
  const config = useRuntimeConfig();
  const authStore = useAuthStore();

  return useLazyFetch(request, {
    baseURL: opts?.baseURL ?? config.public.baseUrl,
    headers: {
      Authorization: `Bearer ${authStore.accessToken}`,
      accept: `application/json`,
      ...(opts?.headers ?? {}),
    },
    retry: 1,
    async onResponseError(context) {
      if (context.response.status === 401) {
        authStore.revokeAccessToken();

        navigateTo("/auth/login");
        return;
      }

      const data = context.response._data;

      if (data.error) {
        alert(data.error);
      }
    },
    ...opts,
  });
};

export const $useFetchAPI = async <T>(
  request: Parameters<typeof $fetch<T>>[0],
  fetchOptions?: Parameters<typeof $fetch<T>>[1]
) => {
  const config = useRuntimeConfig();
  const authStore = useAuthStore();

  return await $fetch<T>(request, {
    ...fetchOptions,
    baseURL: fetchOptions?.baseURL ?? config.public.baseUrl,
    headers: {
      Authorization: `Bearer ${authStore.accessToken}`,
      accept: `application/json`,
      ...(fetchOptions?.headers ?? {}),
    },
    onResponseError(context) {
      console.log(context);
      const data = context.response._data;

      if (data.error) {
        alert(data.error);
      }
    },
  });
};
