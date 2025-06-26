import { Request, Response, NextFunction } from 'express';

export function tenantMiddleware(req: Request, res: Response, next: NextFunction) {
  // In production, extract tenant from subdomain or JWT
  // For now, just continue
  next();
}
