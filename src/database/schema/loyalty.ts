import { Schema, model } from 'mongoose';

export interface ILoyalty {
  tenantId: string;
  customerId: string;
  points: number;
  tier: string; // e.g. Bronze, Silver, Gold, Platinum
  history: Array<{
    date: Date;
    type: 'earn' | 'redeem' | 'adjustment';
    amount: number;
    description?: string;
  }>;
  createdAt: Date;
  updatedAt: Date;
}

const LoyaltySchema = new Schema<ILoyalty>({
  tenantId: { type: String, required: true, index: true },
  customerId: { type: String, required: true },
  points: { type: Number, default: 0 },
  tier: { type: String, default: 'Bronze' },
  history: [{
    date: { type: Date, required: true },
    type: { type: String, enum: ['earn', 'redeem', 'adjustment'], required: true },
    amount: { type: Number, required: true },
    description: String
  }]
}, {
  timestamps: true
});

export const Loyalty = model<ILoyalty>('Loyalty', LoyaltySchema);
