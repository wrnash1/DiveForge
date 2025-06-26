import { Schema, model } from 'mongoose';

export interface IIntegration {
  tenantId: string;
  type: 'agency' | 'payment' | 'accounting' | 'marketing' | 'ecommerce' | 'other';
  name: string;
  provider: string;
  config: Record<string, any>;
  status: 'active' | 'inactive' | 'error';
  lastSync?: Date;
  errorMessage?: string;
  createdAt: Date;
  updatedAt: Date;
}

const IntegrationSchema = new Schema<IIntegration>({
  tenantId: { type: String, required: true, index: true },
  type: { type: String, enum: ['agency', 'payment', 'accounting', 'marketing', 'ecommerce', 'other'], required: true },
  name: { type: String, required: true },
  provider: { type: String, required: true },
  config: { type: Schema.Types.Mixed, required: true },
  status: { type: String, enum: ['active', 'inactive', 'error'], default: 'active' },
  lastSync: Date,
  errorMessage: String
}, {
  timestamps: true
});

export const Integration = model<IIntegration>('Integration', IntegrationSchema);
