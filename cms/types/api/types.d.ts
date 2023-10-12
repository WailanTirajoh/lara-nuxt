declare global {
  interface ApiResponse<T> {
    message: string;
    data: T;
  }
}

export {};
