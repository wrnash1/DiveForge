import { Schema, model } from 'mongoose';

export interface IPhoto {
  tenantId: string;
  uploadedBy: string;
  type: 'certification' | 'site' | 'event' | 'customer' | 'other';
  url: string;
  description?: string;
  tags?: string[];
  relatedResource?: string; // e.g. customerId, diveSiteId, eventId
  consent: {
    forCertification: boolean;
    forMarketing: boolean;
    forSocial: boolean;
    withdrawn?: boolean;
    consentedAt: Date;
    withdrawnAt?: Date;
  };
  createdAt: Date;
  updatedAt: Date;
}

const PhotoSchema = new Schema<IPhoto>({
  tenantId: { type: String, required: true, index: true },
  uploadedBy: { type: String, required: true },
  type: { type: String, enum: ['certification', 'site', 'event', 'customer', 'other'], required: true },
  url: { type: String, required: true },
  description: String,
  tags: [String],
  relatedResource: String,
  consent: {
    forCertification: { type: Boolean, default: false },
    forMarketing: { type: Boolean, default: false },
    forSocial: { type: Boolean, default: false },
    withdrawn: { type: Boolean, default: false },
    consentedAt: { type: Date, required: true },
    withdrawnAt: Date
  }
}, {
  timestamps: true
});

export const Photo = model<IPhoto>('Photo', PhotoSchema);
