import { Schema, model } from 'mongoose';

export interface IEvent {
  tenantId: string;
  name: string;
  description?: string;
  type: 'trip' | 'course' | 'community' | 'site' | 'social' | 'other';
  start: Date;
  end: Date;
  location?: string;
  siteId?: string;
  organizerId?: string;
  participants: string[]; // customer IDs
  maxParticipants?: number;
  status: 'scheduled' | 'completed' | 'cancelled';
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const EventSchema = new Schema<IEvent>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  description: String,
  type: { type: String, enum: ['trip', 'course', 'community', 'site', 'social', 'other'], required: true },
  start: { type: Date, required: true },
  end: { type: Date, required: true },
  location: String,
  siteId: String,
  organizerId: String,
  participants: [String],
  maxParticipants: Number,
  status: { type: String, enum: ['scheduled', 'completed', 'cancelled'], default: 'scheduled' },
  notes: String
}, {
  timestamps: true
});

export const Event = model<IEvent>('Event', EventSchema);
