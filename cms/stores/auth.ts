import type { LoginRequest, LoginResponse, ProfileResponse } from "~/types/api/auth";

export const useAuthStore = defineStore("auth", () => {
  const accessToken = useCookie("access_token");
  const profile = ref();
  const isAuthenticated = computed(() => !!accessToken.value);

  function clearProfile() {
    profile.value = null;
  }

  function revokeAccessToken() {
    accessToken.value = null;
  }

  async function getUserProfile() {
    const { data } = await useFetchAPI<ProfileResponse>("/auth/profile");
    if (data.value) {
      profile.value = data.value.data;
    }
  }

  const login = (loginRequest: LoginRequest) => {
    return useFetchAPI<ApiResponse<LoginResponse>>("/auth/login", {
      method: "post",
      body: loginRequest,
    });
  };

  const logout = () => {
    return useFetchAPI("/auth/logout", {
      method: "delete",
    });
  };

  return {
    profile,
    isAuthenticated,
    accessToken,
    revokeAccessToken,
    getUserProfile,
    clearProfile,
    login,
    logout,
  };
});
