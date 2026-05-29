@extends('emails.email-template')

@section('title', 'Transfer Status Update')
@section('subtitle', $withdrawal->status == 'Processed' ? 'Your transfer request has been approved' : 'Your transfer request is being processed')
@section('company_name', $settings->site_name)

@section('greeting', 'Hello ' . ($foramin ? 'Admin' : $user->name))

@section('content')
<div style="text-align: center; margin-bottom: 30px;">
    @if ($withdrawal->status == 'Processed')
    <div style="display: inline-block; padding: 20px; border-radius: 50%; background-color: #DCFCE7; margin-bottom: 20px;">
        <span style="font-size: 32px;">✅</span>
    </div>
    <div style="font-size: 24px; font-weight: 700; color: #16A34A; margin-bottom: 15px;">Transfer Approved</div>
    @else
    <div style="display: inline-block; padding: 20px; border-radius: 50%; background-color: #FEF3C7; margin-bottom: 20px;">
        <span style="font-size: 32px;">⏳</span>
    </div>
    <div style="font-size: 24px; font-weight: 700; color: #D97706; margin-bottom: 15px;">Transfer Processing</div>
    @endif
</div>

@if ($foramin)
<div style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-radius: 12px; padding: 25px; margin-bottom: 30px;">
    <p style="margin-top: 0; font-weight: 500;">This is to inform you that {{$user->name}} has made a transfer request of <span style="font-weight: 700; color: #4F46E5;">{{$settings->currency.$withdrawal->amount}}</span> to {{$withdrawal->accountname}}. Please login to your bank website to review and take necessary action.</p>
</div>
@else
    @if ($withdrawal->status == 'Processed')
    <div style="background: linear-gradient(135deg, #DCFCE7 0%, #BBF7D0 100%); border-radius: 12px; padding: 25px; margin-bottom: 30px;">
        <p style="margin-top: 0; font-weight: 500;">Your transfer request of <span style="font-weight: 700; color: #16A34A;">{{$settings->currency.$withdrawal->amount}}</span> to {{$withdrawal->accountname}}, {{$withdrawal->bankname}} has been approved.</p>
        
        <p style="margin-bottom: 0;">The funds have been successfully processed and sent to the specified account.</p>
    </div>
    @else
    <div style="background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%); border-radius: 12px; padding: 25px; margin-bottom: 30px;">
        <p style="margin-top: 0; font-weight: 500;">Your transfer request of <span style="font-weight: 700; color: #D97706;">{{$settings->currency.$withdrawal->amount}}</span> to {{$withdrawal->accountname}}, {{$withdrawal->bankname}} has been confirmed.</p>
        
        <p style="margin-bottom: 0;">The beneficiary is expected to be credited within 
        @if($withdrawal->payment_mode == 'International Wire Transfer')
        <span style="font-weight: 700;">72 hours</span>.
        @else
        <span style="font-weight: 700;">an hour</span>.
        @endif
        </p>
    </div>
    @endif
@endif
@endsection

@section('additional_content')
@if (!$foramin && $withdrawal->status != 'Processed')
<div class="transaction-details" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin: 40px 0;">
    <div class="transaction-details-header" style="background: linear-gradient(90deg, #4F46E5 0%, #6366F1 100%); color: white; padding: 15px; font-size: 16px; font-weight: 600;">
        Transaction Details
    </div>
    <div class="transaction-details-body" style="padding: 20px; background-color: #FAFAFA;">
        <div class="transaction-details-row" style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">
            <div class="transaction-details-label" style="color: #6B7280; font-weight: 500;">Account Number:</div>
            <div class="transaction-details-value" style="font-weight: 600; color: #111827;">{{$withdrawal->accountnumber}}</div>
        </div>
        <div class="transaction-details-row" style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">
            <div class="transaction-details-label" style="color: #6B7280; font-weight: 500;">Account Name:</div>
            <div class="transaction-details-value" style="font-weight: 600; color: #111827;">{{$withdrawal->accountname}}</div>
        </div>
        <div class="transaction-details-row" style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">
            <div class="transaction-details-label" style="color: #6B7280; font-weight: 500;">Description:</div>
            <div class="transaction-details-value" style="font-weight: 600; color: #111827;">{{$withdrawal->Description}}</div>
        </div>
        <div class="transaction-details-row" style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">
            <div class="transaction-details-label" style="color: #6B7280; font-weight: 500;">Total Amount:</div>
            <div class="transaction-details-value" style="font-weight: 700; color: #4F46E5; font-size: 16px;">{{$settings->currency.$withdrawal->amount}}</div>
        </div>
        <div class="transaction-details-row" style="display: flex; justify-content: space-between; margin-bottom: 12px; border-bottom: 1px solid #E5E7EB; padding-bottom: 12px;">
            <div class="transaction-details-label" style="color: #6B7280; font-weight: 500;">Date:</div>
            <div class="transaction-details-value" style="font-weight: 600; color: #111827;">{{ \Carbon\Carbon::parse($withdrawal->created_at)->toDayDateTimeString() }}</div>
        </div>
        <div class="transaction-details-row" style="display: flex; justify-content: space-between;">
            <div class="transaction-details-label" style="color: #6B7280; font-weight: 500;">Available Balance:</div>
            <div class="transaction-details-value" style="font-weight: 700; color: #16A34A; font-size: 16px;">{{$settings->currency.$withdrawal->bal}}</div>
        </div>
    </div>
</div>

@if($withdrawal->payment_mode == 'International Wire Transfer')
<div style="border-left: 4px solid #F97316; padding-left: 15px; margin: 30px 0; background-color: #FFF7ED; padding: 20px; border-radius: 8px;">
    <h3 style="margin-top: 0; color: #C2410C; font-size: 16px;">International Wire Transfer Information</h3>
    <p style="margin-bottom: 10px; color: #7C2D12;">International transfers typically take 2-3 business days to process due to additional security verifications.</p>
    <p style="margin-bottom: 0; color: #7C2D12;">Please note that intermediary banks may charge additional fees.</p>
</div>
@endif

<div style="margin: 40px 0; text-align: center;">
    <a href="{{ url('/dashboard/transactions') }}" style="display: inline-block; background: linear-gradient(90deg, #4F46E5 0%, #6366F1 100%); color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">View All Transactions</a>
</div>
@endif
@endsection

@section('help_text')
<div style="padding: 20px; background-color: #F9FAFB; border-radius: 8px; margin-top: 40px;">
    <h3 style="margin-top: 0; color: #4B5563; font-size: 16px;">Need Assistance?</h3>
    <p style="margin-bottom: 15px;">If you have any questions about this transaction or did not authorize this transfer, please contact our support team immediately.</p>
    
    <div style="display: flex; align-items: center; margin-bottom: 0;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #EEF2FF; display: flex; justify-content: center; align-items: center; margin-right: 15px;">
            <span style="color: #4F46E5; font-weight: bold;">📧</span>
        </div>
        <div>
            <a href="mailto:{{ $settings->contact_email }}" style="color: #4F46E5; font-weight: 600; text-decoration: none;">{{ $settings->contact_email }}</a>
        </div>
    </div>
</div>
@endsection

@section('footer')
© {{ date('Y') }} {{ $settings->site_name }} | All Rights Reserved | Secure Banking Solutions
@endsection
