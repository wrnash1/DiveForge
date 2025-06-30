import { Schema, model } from 'mongoose';

export interface IAuditLog {
  tenantId: string;
  userId?: string;
  action: string; // e.g. 'create', 'update', 'delete', 'login', etc.
  resource: string; // e.g. 'customer', 'equipment', etc.
  resourceId?: string;
  details?: Record<string, any>;
  timestamp: Date;
  ip?: string;
  createdAt: Date;
  updatedAt: Date;
}

const AuditLogSchema = new Schema<IAuditLog>({
  tenantId: { type: String, required: true, index: true },
  userId: String,
  action: { type: String, required: true },
  resource: { type: String, required: true },
  resourceId: String,
  details: Schema.Types.Mixed,
  timestamp: { type: Date, required: true },
  ip: String
}, {
  timestamps: true
});

export const AuditLog = model<IAuditLog>('AuditLog', AuditLogSchema);
