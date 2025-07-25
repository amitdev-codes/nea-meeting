# Google OAuth Verification Requirements

## Required Information

### 1. OAuth Consent Screen Setup
- **App name**: Meeting Management System
- **User support email**: Your email address
- **App logo**: Upload a logo (120x120px recommended)
- **App domain**: Your domain (e.g., meetingapp.com)
- **Authorized domains**: Add your domain
- **Developer contact information**: Your email
- **Privacy policy URL**: Required for verification
- **Terms of service URL**: Required for verification

### 2. App Information
- **App description**: Clear description of what your app does
- **App screenshots**: Show the app in action
- **YouTube demo video**: Optional but recommended

### 3. Required Documents
- **Privacy Policy**: Must explain:
  - What data you collect
  - How you use it
  - How users can delete their data
  - Your contact information
- **Terms of Service**: Standard terms for your app

### 4. Scopes Justification
For each scope you request, explain:
- **Why you need it**
- **How you use it**
- **What user benefit it provides**

For Calendar API:
- `https://www.googleapis.com/auth/calendar` - "To sync user meetings with their Google Calendar"
- `https://www.googleapis.com/auth/calendar.events` - "To create, update, and delete calendar events"

### 5. Domain Verification
- **Verify domain ownership** in Google Search Console
- **Add domain** to authorized domains list

## Verification Process Timeline
- **Review time**: 4-6 weeks typically
- **Additional info requests**: Common, respond quickly
- **Approval**: You'll get email notification

## Tips for Approval
1. **Be specific** in descriptions
2. **Provide clear screenshots** showing OAuth flow
3. **Explain user benefit** clearly
4. **Ensure privacy policy** is comprehensive
5. **Use professional email** for contact
6. **Test thoroughly** before submission

## Common Rejection Reasons
- Missing privacy policy
- Unclear app description
- Screenshots don't match app functionality
- Requesting unnecessary scopes
- Domain not verified