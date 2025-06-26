import { Schema, model } from 'mongoose';

export interface IBuddy {
  tenantId: string;
  customerId: string;
  buddyId: string;
  status: 'pending' | 'accepted' | 'rejected' | 'blocked';
  requestedAt: Date;
  acceptedAt?: Date;
  notes?: string;
  createdAt: Date;
  updatedAt: Date;
}

const BuddySchema = new Schema<IBuddy>({
  tenantId: { type: String, required: true, index: true },
  customerId: { type: String, required: true },
  buddyId: { type: String, required: true },
  status: { type: String, enum: ['pending', 'accepted', 'rejected', 'blocked'], default: 'pending' },
  requestedAt: { type: Date, required: true },
  acceptedAt: Date,
  notes: String
}, {
  timestamps: true
});

export const Buddy = model<IBuddy>('Buddy', BuddySchema);
