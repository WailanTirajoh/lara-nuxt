import type { PermissionResponse } from "~/types/api/permission";

export const usePermissionStore = defineStore("permission", () => {
  const get = () => {
    return $useFetchAPI<ApiResponse<PermissionResponse>>("/permissions");
  };

  return { get };
});
