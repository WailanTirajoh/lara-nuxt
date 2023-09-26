export const useAuthStore = defineStore("auth", () => {
  const accessToken = useCookie("access_token");
  const profile = ref();
  const isAuthenticated = computed(() => !!accessToken.value);

  function revokeAccessToken() {
    accessToken.value = null;
  }

  interface ProfileResponse {
    message: string;
    data: {
      id: number;
      name: string;
      email: string;
      email_verified_at: string | null;
      created_at: string | null;
      updated_at: string | null;
    };
  }

  async function getUserProfile() {
    const { data } = await useFetchAPI<ProfileResponse>("/auth/profile");
    if (data.value) {
      profile.value = data.value.data;
    }
  }

  return {
    profile,
    isAuthenticated,
    accessToken,
    revokeAccessToken,
    getUserProfile,
  };
});
