import { Schema, model } from 'mongoose';

export interface IB2BAccount {
  tenantId: string;
  name: string;
  type: 'dive-center' | 'instructor' | 'club' | 'reseller' | 'corporate' | 'education' | 'government' | 'insurance';
  contactName: string;
  email: string;
  phone?: string;
  address?: string;
  ein?: string;
  taxExempt: boolean;
  taxDocs?: Array<{
    type: string;
    fileUrl: string;
    expiresAt?: Date;
  }>;
  creditLimit?: number;
  paymentTerms?: string;
  accountStatus: 'active' | 'suspended' | 'closed';
  locations: string[];
  authorizedBuyers: Array<{
    name: string;
    email: string;
    phone?: string;
    limit?: number;
    department?: string;
  }>;
  customPricing?: Record<string, number>;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const B2BAccountSchema = new Schema<IB2BAccount>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  type: { type: String, required: true, enum: ['dive-center', 'instructor', 'club', 'reseller', 'corporate', 'education', 'government', 'insurance'] },
  contactName: { type: String, required: true },
  email: { type: String, required: true },
  phone: String,
  address: String,
  ein: String,
  taxExempt: { type: Boolean, default: false },
  taxDocs: [{
    type: String,
    fileUrl: String,
    expiresAt: Date
  }],
  creditLimit: Number,
  paymentTerms: String,
  accountStatus: { type: String, enum: ['active', 'suspended', 'closed'], default: 'active' },
  locations: [String],
  authorizedBuyers: [{
    name: String,
    email: String,
    phone: String,
    limit: Number,
    department: String
  }],
  customPricing: Schema.Types.Mixed,
  notes: String
}, {
  timestamps: true
});

export const B2BAccount = model<IB2BAccount>('B2BAccount', B2BAccountSchema);
