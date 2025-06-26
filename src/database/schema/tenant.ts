import { Schema, model } from 'mongoose';

export interface ITenant {
  name: string;
  subdomain: string;
  agencies: string[];
  settings: {
    theme: string;
    features: string[];
    customization: Record<string, any>;
  };
  subscription: {
    plan: 'basic' | 'professional' | 'enterprise' | 'franchise';
    status: 'active' | 'suspended' | 'cancelled';
    expiresAt: Date;
  };
  createdAt: Date;
  updatedAt: Date;
}

const TenantSchema = new Schema<ITenant>({
  name: { type: String, required: true },
  subdomain: { type: String, required: true, unique: true },
  agencies: [{ type: String, required: true }],
  settings: {
    theme: { type: String, default: 'default' },
    features: [{ type: String }],
    customization: { type: Schema.Types.Mixed }
  },
  subscription: {
    plan: { type: String, required: true, enum: ['basic', 'professional', 'enterprise', 'franchise'] },
    status: { type: String, required: true, enum: ['active', 'suspended', 'cancelled'] },
    expiresAt: { type: Date, required: true }
  }
}, {
  timestamps: true
});

export const Tenant = model<ITenant>('Tenant', TenantSchema);
