import { RepositoryInterface } from "@/module/shared/domain/repository/repository.interface";
import { getAuthToken } from "@/lib/auth-token";
import { redirect } from "next/navigation";

export abstract class ApiRepository<T> implements RepositoryInterface<T> {
  constructor(
    protected readonly baseUrl: string,
    protected readonly endpoint: string
  ) {}

  protected async getHeaders(): Promise<HeadersInit> {
    const token = await getAuthToken();

    const headers: Record<string, string> = {
      "Content-Type": "application/json",
      Accept: "application/json",
    };

    if (token) {
      headers["Authorization"] = `Bearer ${token}`;
    }

    return headers;
  }

  protected async handleResponse(res: Response) {
    const result = await res.json();

    if (!result.success) {
      if (result.code === 401) {
        redirect("/login");
      }

      throw new Error(result.message || "Une erreur est survenue");
    }

    return result.data;
  }

  async findAll(): Promise<T[]> {
    const res = await fetch(`${this.baseUrl}${this.endpoint}`, {
      headers: await this.getHeaders(),
    });

    const data = await this.handleResponse(res);
    return data.map((item: any) => this.mapToDomain(item));
  }

  abstract findByUid(uid: string): Promise<T | null>;

  abstract save(entity: T): Promise<void>;

  abstract findOneBy(criteria: Record<string, any>): Promise<T | null>;

  abstract count(criteria?: Record<string, any>): Promise<number>;

  protected abstract mapToDomain(raw: any): T;
}
