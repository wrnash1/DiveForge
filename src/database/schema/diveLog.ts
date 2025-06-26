import { Schema, model } from 'mongoose';

export interface IDiveLog {
  tenantId: string;
  customerId: string;
  date: Date;
  siteId?: string;
  siteName?: string;
  gps?: { lat: number; lng: number };
  maxDepth?: number;
  avgDepth?: number;
  duration?: number;
  temperature?: number;
  visibility?: string;
  buddy?: string;
  instructor?: string;
  equipmentUsed?: string[];
  notes?: string;
  photos?: string[];
  rating?: number;
  createdAt: Date;
  updatedAt: Date;
}

const DiveLogSchema = new Schema<IDiveLog>({
  tenantId: { type: String, required: true, index: true },
  customerId: { type: String, required: true },
  date: { type: Date, required: true },
  siteId: String,
  siteName: String,
  gps: {
    lat: Number,
    lng: Number
  },
  maxDepth: Number,
  avgDepth: Number,
  duration: Number,
  temperature: Number,
  visibility: String,
  buddy: String,
  instructor: String,
  equipmentUsed: [String],
  notes: String,
  photos: [String],
  rating: Number
}, {
  timestamps: true
});

export const DiveLog = model<IDiveLog>('DiveLog', DiveLogSchema);
