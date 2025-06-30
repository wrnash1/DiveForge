import { Schema, model } from 'mongoose';

export interface ISubscription {
  tenantId: string;
  plan: 'basic' | 'professional' | 'enterprise' | 'franchise';
  status: 'active' | 'trialing' | 'past_due' | 'cancelled' | 'suspended';
  startDate: Date;
  endDate?: Date;
  renewalDate?: Date;
  billingProvider: string; // e.g. 'stripe', 'paypal'
  billingId?: string;
  features: string[];
  seats: number;
  price: number;
  currency: string;
  trialEndsAt?: Date;
  cancelAtPeriodEnd?: boolean;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const SubscriptionSchema = new Schema<ISubscription>({
  tenantId: { type: String, required: true, index: true },
  plan: { type: String, enum: ['basic', 'professional', 'enterprise', 'franchise'], required: true },
  status: { type: String, enum: ['active', 'trialing', 'past_due', 'cancelled', 'suspended'], default: 'active' },
  startDate: { type: Date, required: true },
  endDate: Date,
  renewalDate: Date,
  billingProvider: { type: String, required: true },
  billingId: String,
  features: [String],
  seats: { type: Number, default: 1 },
  price: { type: Number, required: true },
  currency: { type: String, default: 'USD' },
  trialEndsAt: Date,
  cancelAtPeriodEnd: Boolean,
  notes: String
}, {
  timestamps: true
});

export const Subscription = model<ISubscription>('Subscription', SubscriptionSchema);
