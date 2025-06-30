import { Schema, model } from 'mongoose';

export interface IPayroll {
  tenantId: string;
  staffId: string;
  periodStart: Date;
  periodEnd: Date;
  baseSalary: number;
  bonuses: number;
  commissions: number;
  deductions: number;
  totalPay: number;
  status: 'pending' | 'processed' | 'paid';
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const PayrollSchema = new Schema<IPayroll>({
  tenantId: { type: String, required: true, index: true },
  staffId: { type: String, required: true },
  periodStart: { type: Date, required: true },
  periodEnd: { type: Date, required: true },
  baseSalary: { type: Number, required: true },
  bonuses: { type: Number, default: 0 },
  commissions: { type: Number, default: 0 },
  deductions: { type: Number, default: 0 },
  totalPay: { type: Number, required: true },
  status: { type: String, enum: ['pending', 'processed', 'paid'], default: 'pending' },
  notes: String
}, {
  timestamps: true
});

export const Payroll = model<IPayroll>('Payroll', PayrollSchema);
