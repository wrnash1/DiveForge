import { Schema, model } from 'mongoose';

export interface IAirCard {
  tenantId: string;
  code: string;
  customerId?: string;
  totalFills: number;
  remainingFills: number;
  nitroxEligible: boolean;
  tier?: string;
  issuedAt: Date;
  expiresAt?: Date;
  status: 'active' | 'expired' | 'cancelled';
  locations: string[]; // multi-location support
  history: Array<{
    date: Date;
    type: 'fill' | 'purchase' | 'adjustment';
    amount: number;
    location?: string;
    notes?: string;
  }>;
  createdAt: Date;
  updatedAt: Date;
}

const AirCardSchema = new Schema<IAirCard>({
  tenantId: { type: String, required: true, index: true },
  code: { type: String, required: true, unique: true },
  customerId: String,
  totalFills: { type: Number, required: true },
  remainingFills: { type: Number, required: true },
  nitroxEligible: { type: Boolean, default: false },
  tier: String,
  issuedAt: { type: Date, required: true },
  expiresAt: Date,
  status: { type: String, enum: ['active', 'expired', 'cancelled'], default: 'active' },
  locations: [String],
  history: [{
    date: { type: Date, required: true },
    type: { type: String, enum: ['fill', 'purchase', 'adjustment'], required: true },
    amount: { type: Number, required: true },
    location: String,
    notes: String
  }]
}, {
  timestamps: true
});

export const AirCard = model<IAirCard>('AirCard', AirCardSchema);
