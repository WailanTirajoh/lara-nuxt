export type ToastType = "success" | "info" | "error" | "warning";
export interface Toast {
  id: string;
  message?: string;
  html?: string;
  lifetime?: number;
  type: ToastType;
  jsonMessage?: object | null;
  title?: string | null;
}
export interface ToastDeafult {
  message: string;
  lifetime?: number;
  title?: string;
}
export interface ToastSuccess extends ToastDeafult {}
export interface ToastError extends ToastDeafult {}
export interface ToastWarning extends ToastDeafult {}
export interface ToastInfo extends ToastDeafult {}
