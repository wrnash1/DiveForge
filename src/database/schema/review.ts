import { Schema, model } from 'mongoose';

export interface IReview {
  tenantId: string;
  type: 'site' | 'trip' | 'instructor' | 'equipment' | 'course' | 'other';
  referenceId: string; // e.g. siteId, tripId, instructorId, etc.
  customerId: string;
  rating: number;
  comment?: string;
  photos?: string[];
  createdAt: Date;
  updatedAt: Date;
}

const ReviewSchema = new Schema<IReview>({
  tenantId: { type: String, required: true, index: true },
  type: { type: String, enum: ['site', 'trip', 'instructor', 'equipment', 'course', 'other'], required: true },
  referenceId: { type: String, required: true },
  customerId: { type: String, required: true },
  rating: { type: Number, required: true, min: 1, max: 5 },
  comment: String,
  photos: [String]
}, {
  timestamps: true
});

export const Review = model<IReview>('Review', ReviewSchema);
