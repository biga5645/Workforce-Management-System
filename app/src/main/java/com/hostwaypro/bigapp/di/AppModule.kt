package com.hostwaypro.bigapp.di

import com.google.firebase.auth.FirebaseAuth
import com.google.firebase.firestore.FirebaseFirestore
import com.hostwaypro.bigapp.data.repository.AuthRepository
import com.hostwaypro.bigapp.data.repository.AuthRepositoryImpl
import com.hostwaypro.bigapp.data.repository.UserRepository
import com.hostwaypro.bigapp.data.repository.UserRepositoryImpl
import com.hostwaypro.bigapp.data.repository.WorkRecordRepository
import com.hostwaypro.bigapp.data.repository.WorkRecordRepositoryImpl
import dagger.Binds
import dagger.Module
import dagger.Provides
import dagger.hilt.InstallIn
import dagger.hilt.components.SingletonComponent
import javax.inject.Singleton

@Module
@InstallIn(SingletonComponent::class)
abstract class RepositoryModule {

    @Binds
    @Singleton
    abstract fun bindAuthRepository(impl: AuthRepositoryImpl): AuthRepository

    @Binds
    @Singleton
    abstract fun bindUserRepository(impl: UserRepositoryImpl): UserRepository

    @Binds
    @Singleton
    abstract fun bindWorkRecordRepository(impl: WorkRecordRepositoryImpl): WorkRecordRepository
}

@Module
@InstallIn(SingletonComponent::class)
object AppModule {

    @Provides
    @Singleton
    fun provideFirebaseAuth(): FirebaseAuth = FirebaseAuth.getInstance()

    @Provides
    @Singleton
    fun provideFirebaseFirestore(): FirebaseFirestore = FirebaseFirestore.getInstance()
}
