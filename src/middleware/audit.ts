import { AuditLog } from '../database/schema/auditLog';

export async function logAudit({
  tenantId,
  userId,
  action,
  resource,
  resourceId,
  details,
  ip
}: {
  tenantId: string;
  userId?: string;
  action: string;
  resource: string;
  resourceId?: string;
  details?: Record<string, any>;
  ip?: string;
}) {
  await AuditLog.create({
    tenantId,
    userId,
    action,
    resource,
    resourceId,
    details,
    timestamp: new Date(),
    ip
  });
}
