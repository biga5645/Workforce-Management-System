package com.hostwaypro.bigapp.data.model

import com.google.firebase.firestore.ServerTimestamp
import java.util.Date

enum class PaymentType {
    DAILY, HOURLY, MIXED
}

data class WorkRecord(
    val id: String = "",
    val workerId: String = "",
    val workerName: String = "",
    val date: Date? = null,
    val days: Double = 0.0,
    val hours: Double = 0.0,
    val paymentType: PaymentType = PaymentType.MIXED,
    val hourlyRate: Double = 0.0,
    val dailyRate: Double = 0.0,
    val hoursPerDay: Double = 8.0,
    val amount: Double = 0.0,
    val note: String = "",
    @ServerTimestamp
    val createdAt: Date? = null,
    @ServerTimestamp
    val updatedAt: Date? = null,
    val createdBy: String = ""
) {
    // Total Amount = (Days × Daily Rate) + (Hours × Hourly Rate)
    fun calculateAmount(): Double {
        return (days * dailyRate) + (hours * hourlyRate)
    }
}
