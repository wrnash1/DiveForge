import { RequestHandler } from 'express';

export function configureSecurityMiddleware(): RequestHandler {
  // Add helmet, rate limiting, etc. as needed
  return (req, res, next) => next();
}
