import { Schema, model } from 'mongoose';

export interface ICommission {
  tenantId: string;
  staffId: string;
  type: 'instructor' | 'sales' | 'group' | 'override' | 'bonus' | 'referral';
  referenceType: string; // e.g. 'course', 'equipment', 'trip'
  referenceId: string;
  amount: number;
  rate?: number;
  period?: string; // e.g. '2024-Q2'
  status: 'pending' | 'approved' | 'paid';
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const CommissionSchema = new Schema<ICommission>({
  tenantId: { type: String, required: true, index: true },
  staffId: { type: String, required: true },
  type: { type: String, required: true, enum: ['instructor', 'sales', 'group', 'override', 'bonus', 'referral'] },
  referenceType: { type: String, required: true },
  referenceId: { type: String, required: true },
  amount: { type: Number, required: true },
  rate: Number,
  period: String,
  status: { type: String, enum: ['pending', 'approved', 'paid'], default: 'pending' },
  notes: String
}, {
  timestamps: true
});

export const Commission = model<ICommission>('Commission', CommissionSchema);
