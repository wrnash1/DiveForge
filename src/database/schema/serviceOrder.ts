import { Schema, model } from 'mongoose';

export interface IServiceOrder {
  tenantId: string;
  equipmentId: string;
  customerId?: string;
  workOrderNumber: string;
  status: 'open' | 'in-progress' | 'awaiting-parts' | 'completed' | 'cancelled' | 'warranty';
  serviceType: string; // e.g. 'repair', 'maintenance', 'inspection'
  description: string;
  parts: Array<{
    partNumber: string;
    name: string;
    quantity: number;
    unitCost: number;
    markup: number;
    total: number;
  }>;
  labor: Array<{
    staffId: string;
    hours: number;
    rate: number;
    total: number;
  }>;
  warranty: {
    isWarranty: boolean;
    claimNumber?: string;
    status?: string;
  };
  serviceHistory: Array<{
    date: Date;
    notes: string;
    staffId?: string;
  }>;
  qualityControl: {
    passed: boolean;
    notes?: string;
    checkedBy?: string;
    checkedAt?: Date;
  };
  scheduledAt?: Date;
  completedAt?: Date;
  notifications: Array<{
    type: string;
    sentAt: Date;
    message: string;
  }>;
  recurring: {
    isRecurring: boolean;
    interval?: string; // e.g. '6 months'
    nextDue?: Date;
  };
  createdAt: Date;
  updatedAt: Date;
}

const ServiceOrderSchema = new Schema<IServiceOrder>({
  tenantId: { type: String, required: true, index: true },
  equipmentId: { type: String, required: true },
  customerId: String,
  workOrderNumber: { type: String, required: true },
  status: { type: String, enum: ['open', 'in-progress', 'awaiting-parts', 'completed', 'cancelled', 'warranty'], default: 'open' },
  serviceType: { type: String, required: true },
  description: { type: String, required: true },
  parts: [{
    partNumber: String,
    name: String,
    quantity: Number,
    unitCost: Number,
    markup: Number,
    total: Number
  }],
  labor: [{
    staffId: String,
    hours: Number,
    rate: Number,
    total: Number
  }],
  warranty: {
    isWarranty: { type: Boolean, default: false },
    claimNumber: String,
    status: String
  },
  serviceHistory: [{
    date: Date,
    notes: String,
    staffId: String
  }],
  qualityControl: {
    passed: Boolean,
    notes: String,
    checkedBy: String,
    checkedAt: Date
  },
  scheduledAt: Date,
  completedAt: Date,
  notifications: [{
    type: String,
    sentAt: Date,
    message: String
  }],
  recurring: {
    isRecurring: { type: Boolean, default: false },
    interval: String,
    nextDue: Date
  }
}, {
  timestamps: true
});

export const ServiceOrder = model<IServiceOrder>('ServiceOrder', ServiceOrderSchema);
