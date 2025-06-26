import { Schema, model } from 'mongoose';

export interface IGiftCard {
  tenantId: string;
  code: string;
  customerId?: string;
  amount: number;
  balance: number;
  issuedAt: Date;
  expiresAt?: Date;
  isDigital: boolean;
  delivered: boolean;
  status: 'active' | 'redeemed' | 'expired' | 'cancelled';
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const GiftCardSchema = new Schema<IGiftCard>({
  tenantId: { type: String, required: true, index: true },
  code: { type: String, required: true, unique: true },
  customerId: String,
  amount: { type: Number, required: true },
  balance: { type: Number, required: true },
  issuedAt: { type: Date, required: true },
  expiresAt: Date,
  isDigital: { type: Boolean, default: true },
  delivered: { type: Boolean, default: false },
  status: { type: String, enum: ['active', 'redeemed', 'expired', 'cancelled'], default: 'active' },
  notes: String
}, {
  timestamps: true
});

export const GiftCard = model<IGiftCard>('GiftCard', GiftCardSchema);
