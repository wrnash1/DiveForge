import { Schema, model } from 'mongoose';

export interface IBooking {
  tenantId: string;
  customerId: string;
  type: 'course' | 'trip' | 'equipment';
  referenceId: string; // courseId, tripId, or equipmentId
  start: Date;
  end: Date;
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
  participants?: string[]; // for group bookings
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const BookingSchema = new Schema<IBooking>({
  tenantId: { type: String, required: true, index: true },
  customerId: { type: String, required: true },
  type: { type: String, required: true, enum: ['course', 'trip', 'equipment'] },
  referenceId: { type: String, required: true },
  start: { type: Date, required: true },
  end: { type: Date, required: true },
  status: { type: String, enum: ['pending', 'confirmed', 'cancelled', 'completed'], default: 'pending' },
  participants: [String],
  notes: String
}, {
  timestamps: true
});

export const Booking = model<IBooking>('Booking', BookingSchema);
