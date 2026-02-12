export interface RepositoryInterface<T> {
  save(entity: T): Promise<void>;
  findByUid(uid: string): Promise<T | null>;
  findOneBy(criteria: Record<string, any>): Promise<T | null>;
  findAll(): Promise<T[]>;
  count(criteria?: Record<string, any>): Promise<number>;
}
