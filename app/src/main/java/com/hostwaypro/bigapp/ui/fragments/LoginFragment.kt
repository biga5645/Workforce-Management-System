package com.hostwaypro.bigapp.ui.fragments

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.Lifecycle
import androidx.lifecycle.lifecycleScope
import androidx.lifecycle.repeatOnLifecycle
import androidx.navigation.fragment.findNavController
import com.hostwaypro.bigapp.R
import com.hostwaypro.bigapp.databinding.FragmentLoginBinding
import com.hostwaypro.bigapp.ui.viewmodel.AuthViewModel
import com.hostwaypro.bigapp.util.Resource
import dagger.hilt.android.AndroidEntryPoint
import kotlinx.coroutines.launch

@AndroidEntryPoint
class LoginFragment : Fragment() {

    private var _binding: FragmentLoginBinding? = null
    private val binding get() = _binding!!
    private val viewModel: AuthViewModel by viewModels()

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentLoginBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        binding.btnLogin.setOnClickListener {
            val email = binding.etEmail.text.toString()
            val password = binding.etPassword.text.toString()
            if (email.isNotEmpty() && password.isNotEmpty()) {
                viewModel.signIn(email, password)
            }
        }

        binding.btnGoToRegister.setOnClickListener {
            findNavController().navigate(R.id.action_loginFragment_to_registerFragment)
        }

        viewLifecycleOwner.lifecycleScope.launch {
            viewLifecycleOwner.repeatOnLifecycle(Lifecycle.State.STARTED) {
                viewModel.authState.collect { resource ->
                    when (resource) {
                        is Resource.Loading -> binding.progressBar.visibility = View.VISIBLE
                        is Resource.Success -> {
                            binding.progressBar.visibility = View.GONE
                            val user = resource.data
                            if (user != null) {
                                val currentDest = findNavController().currentDestination?.id
                                if (currentDest == R.id.loginFragment) {
                                    val status = user.getStatusEnum()
                                    val role = user.getRoleEnum()
                                    
                                    when {
                                        status == com.hostwaypro.bigapp.data.model.UserStatus.PENDING -> {
                                            findNavController().navigate(R.id.action_loginFragment_to_pendingApprovalFragment)
                                        }
                                        role == com.hostwaypro.bigapp.data.model.UserRole.ADMIN -> {
                                            findNavController().navigate(R.id.action_loginFragment_to_adminDashboardFragment)
                                        }
                                        else -> {
                                            findNavController().navigate(R.id.action_loginFragment_to_workerDashboardFragment)
                                        }
                                    }
                                }
                            } else {
                                // Logic if Firestore record is missing
                                if (com.google.firebase.auth.FirebaseAuth.getInstance().currentUser != null) {
                                    Toast.makeText(requireContext(), "Account record not found in Firestore. Please contact support.", Toast.LENGTH_LONG).show()
                                }
                            }
                        }
                        is Resource.Error -> {
                            binding.progressBar.visibility = View.GONE
                            if (resource.message.isNotEmpty()) {
                                Toast.makeText(requireContext(), resource.message, Toast.LENGTH_SHORT).show()
                            }
                        }
                    }
                }
            }
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
