import { Schema, model } from 'mongoose';

export interface ICustomer {
  tenantId: string;
  email: string;
  name: string;
  phone?: string;
  address?: string;
  certifications: Array<{
    agency: string;
    level: string;
    certNumber?: string;
    issueDate?: Date;
    expiryDate?: Date;
    digitalCardUrl?: string;
  }>;
  loyalty: {
    points: number;
    tier: string;
  };
  preferences?: Record<string, any>;
  isActive: boolean;
  createdAt: Date;
  updatedAt: Date;
}

const CustomerSchema = new Schema<ICustomer>({
  tenantId: { type: String, required: true, index: true },
  email: { type: String, required: true },
  name: { type: String, required: true },
  phone: String,
  address: String,
  certifications: [{
    agency: String,
    level: String,
    certNumber: String,
    issueDate: Date,
    expiryDate: Date,
    digitalCardUrl: String
  }],
  loyalty: {
    points: { type: Number, default: 0 },
    tier: { type: String, default: 'Bronze' }
  },
  preferences: { type: Schema.Types.Mixed },
  isActive: { type: Boolean, default: true }
}, {
  timestamps: true
});

export const Customer = model<ICustomer>('Customer', CustomerSchema);
