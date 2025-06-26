import { Schema, model } from 'mongoose';

export interface ITrip {
  tenantId: string;
  name: string;
  description?: string;
  startDate: Date;
  endDate: Date;
  locations: Array<{
    siteName: string;
    gps?: { lat: number; lng: number };
    notes?: string;
  }>;
  boatId?: string;
  crew: string[];
  capacity: number;
  booked: number;
  price: number;
  status: 'scheduled' | 'in-progress' | 'completed' | 'cancelled';
  participants: string[]; // customer IDs
  weather?: Record<string, any>;
  manifest?: Array<{
    customerId: string;
    checkedIn: boolean;
    notes?: string;
  }>;
  createdAt: Date;
  updatedAt: Date;
}

const TripSchema = new Schema<ITrip>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  description: String,
  startDate: { type: Date, required: true },
  endDate: { type: Date, required: true },
  locations: [{
    siteName: { type: String, required: true },
    gps: {
      lat: Number,
      lng: Number
    },
    notes: String
  }],
  boatId: String,
  crew: [String],
  capacity: { type: Number, required: true },
  booked: { type: Number, default: 0 },
  price: { type: Number, required: true },
  status: { type: String, enum: ['scheduled', 'in-progress', 'completed', 'cancelled'], default: 'scheduled' },
  participants: [String],
  weather: Schema.Types.Mixed,
  manifest: [{
    customerId: String,
    checkedIn: Boolean,
    notes: String
  }]
}, {
  timestamps: true
});

export const Trip = model<ITrip>('Trip', TripSchema);
