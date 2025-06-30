import { Schema, model } from 'mongoose';

export interface IAgency {
  code: string;
  name: string;
  apiEndpoint: string;
  apiCredentials: {
    clientId: string;
    clientSecret: string;
    accessToken?: string;
    refreshToken?: string;
    tokenExpiry?: Date;
  };
  features: {
    digitalCerts: boolean;
    elearning: boolean;
    apiIntegration: boolean;
  };
  certificationLevels: Array<{
    code: string;
    name: string;
    prerequisites: string[];
    equivalencies: Array<{
      agency: string;
      level: string;
    }>;
  }>;
  status: 'active' | 'disabled';
  createdAt: Date;
  updatedAt: Date;
}

const AgencySchema = new Schema<IAgency>({
  code: { type: String, required: true, unique: true },
  name: { type: String, required: true },
  apiEndpoint: { type: String, required: true },
  apiCredentials: {
    clientId: { type: String, required: true },
    clientSecret: { type: String, required: true },
    accessToken: String,
    refreshToken: String,
    tokenExpiry: Date
  },
  features: {
    digitalCerts: { type: Boolean, default: false },
    elearning: { type: Boolean, default: false },
    apiIntegration: { type: Boolean, default: false }
  },
  certificationLevels: [{
    code: String,
    name: String,
    prerequisites: [String],
    equivalencies: [{
      agency: String,
      level: String
    }]
  }],
  status: { type: String, default: 'active', enum: ['active', 'disabled'] }
}, {
  timestamps: true
});

export const Agency = model<IAgency>('Agency', AgencySchema);
