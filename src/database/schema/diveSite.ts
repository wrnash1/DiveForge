import { Schema, model } from 'mongoose';

export interface IDiveSite {
  tenantId: string;
  name: string;
  description?: string;
  gps: { lat: number; lng: number };
  maxDepth?: number;
  avgDepth?: number;
  difficulty: 'beginner' | 'intermediate' | 'advanced' | 'technical';
  features: string[]; // e.g. ['wreck', 'reef', 'wall']
  marineLife?: string[];
  photos?: string[];
  access: 'boat' | 'shore' | 'both';
  conditions?: {
    visibility?: string;
    temperature?: string;
    current?: string;
    weather?: string;
  };
  siteType?: string; // e.g. 'reef', 'wreck', etc.
  amenities?: string[];
  hazards?: string[];
  permitRequired?: boolean;
  region?: string;
  createdAt: Date;
  updatedAt: Date;
}

const DiveSiteSchema = new Schema<IDiveSite>({
  tenantId: { type: String, required: true, index: true },
  name: { type: String, required: true },
  description: String,
  gps: {
    lat: { type: Number, required: true },
    lng: { type: Number, required: true }
  },
  maxDepth: Number,
  avgDepth: Number,
  difficulty: { type: String, enum: ['beginner', 'intermediate', 'advanced', 'technical'], required: true },
  features: [String],
  marineLife: [String],
  photos: [String],
  access: { type: String, enum: ['boat', 'shore', 'both'], required: true },
  conditions: {
    visibility: String,
    temperature: String,
    current: String,
    weather: String
  },
  siteType: String,
  amenities: [String],
  hazards: [String],
  permitRequired: Boolean,
  region: String
}, {
  timestamps: true
});

export const DiveSite = model<IDiveSite>('DiveSite', DiveSiteSchema);
