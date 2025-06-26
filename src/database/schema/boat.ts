import { Schema, model } from 'mongoose';

export interface IBoat {
  tenantId: string;
  name: string;
  registrationNumber?: string;
  capacity: number;
  crew: string[]; // staff IDs
  maintenance: Array<{
    date: Date;
    type: string;
    notes?: string;
    performedBy?: string;
  }>;
  engineHours: number;
  fuelLevel?: number;
  insuranceExpiry?: Date;
  status: 'active' | 'maintenance' | 'retired';
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const BoatSchema = new Schema<IBoat>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  registrationNumber: String,
  capacity: { type: Number, required: true },
  crew: [String],
  maintenance: [{
    date: Date,
    type: String,
    notes: String,
    performedBy: String
  }],
  engineHours: { type: Number, default: 0 },
  fuelLevel: Number,
  insuranceExpiry: Date,
  status: { type: String, enum: ['active', 'maintenance', 'retired'], default: 'active' },
  notes: String
}, {
  timestamps: true
});

export const Boat = model<IBoat>('Boat', BoatSchema);
