package com.hostwaypro.bigapp.data.model

data class AppSettings(
    val dailyRate: Double = 200.0,
    val hourlyRate: Double = 25.0,
    val hoursPerDay: Double = 8.0,
    val currency: String = "DH",
    val language: String = "ar",
    val theme: String = "system"
)
