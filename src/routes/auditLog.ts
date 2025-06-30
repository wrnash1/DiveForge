import express from 'express';
import { AuditLog } from '../database/schema/auditLog';
import { authenticateJWT } from '../middleware/auth';

const router = express.Router();

// List audit logs for tenant (optionally filter by resource or user)
router.get('/', authenticateJWT, async (req, res) => {
  const tenantId = (req as any).user.tenantId;
  const { resource, userId } = req.query;
  const query: any = { tenantId };
  if (resource) query.resource = resource;
  if (userId) query.userId = userId;
  const logs = await AuditLog.find(query).sort({ timestamp: -1 }).limit(200);
  res.json(logs);
});

export const auditLogRoutes = router;
